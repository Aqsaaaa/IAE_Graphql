const { ApolloClient, InMemoryCache, gql, HttpLink } = require('@apollo/client/core');
const fetch = require('cross-fetch');
const db = require('./db');
const e = require('express');

const client = new ApolloClient({
  link: new HttpLink({ uri: 'http://user-service:5000/graphql', fetch }),
  cache: new InMemoryCache(),
});

const resolvers = {
  getMembership: async ({ id }) => {
    const [rows] = await db.query('SELECT * FROM memberships WHERE id = ?', [id]);
    if (rows.length === 0) return null;
    const membership = rows[0];
    try {
      const { data } = await client.query({
          query: gql`
            query GetUser($id: ID!) {
              user(id: $id) {
                name
                id
                phone
              }
            }
          `,
        variables: { id: membership.user_id },
      });
      membership.user = data.user;
    } catch (error) {
      membership.user = error.message.includes('Network error') ? 'Network error' : null;
      console.error('Error fetching user data:', error);
    }
    return membership;
  },

  getAllMemberships: async () => {
    const [rows] = await db.query('SELECT * FROM memberships');
    const membershipsWithUser = await Promise.all(rows.map(async (membership) => {
      try {
        const { data } = await client.query({
          query: gql`
            query GetUser($id: Int!) {
              user(id: $id) {
                id
                name
                phone
              }
            }
          `,
          variables: { id: membership.user_id },
        });
        membership.user = data.user;
      } catch (error) {
        console.error('Error fetching user data for membership id', membership.id, ':', error);
        membership.user = null;
      }
      return membership;
    }));
    return membershipsWithUser;
  },

  createMembership: async ({ input }) => {
    const { user_id, points = 0 } = input;
    const [result] = await db.query(
      'INSERT INTO memberships (user_id, points) VALUES (?, ?)',
      [user_id, points]
    );
    const [rows] = await db.query('SELECT * FROM memberships WHERE id = ?', [result.insertId]);
    return rows[0];
  },

  updateMembership: async ({ id, input }) => {
    const { user_id, points } = input;
    await db.query(
      'UPDATE memberships SET user_id = ?, points = ? WHERE id = ?',
      [user_id, points, id]
    );
    const [rows] = await db.query('SELECT * FROM memberships WHERE id = ?', [id]);
    return rows[0];
  },

  deleteMembership: async ({ id }) => {
    const [result] = await db.query('DELETE FROM memberships WHERE id = ?', [id]);
    return result.affectedRows > 0;
  },
};

module.exports = resolvers;