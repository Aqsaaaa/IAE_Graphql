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
      membership.user = error.message.includes('Network error') ? 'Network error' : null;
      console.error('Error fetching user data:', error);
    }
    return membership;
  },

  getAllMemberships: async () => {
  try {
    const [rows] = await db.query('SELECT * FROM memberships');
    const membershipsWithUser = await Promise.all(
      rows.map(async (membership) => {
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

          return {
            ...membership,
            user: data.user
          };
        } catch (error) {
          console.error(`Error fetching user for membership ${membership.id}:`, error);
          return {
            ...membership,
            user: null
          };
        }
      })
    );

    return membershipsWithUser;

  } catch (dbError) {
    console.error('Database error:', dbError);
    throw new Error('Failed to fetch memberships from database');
  }
},

  Mutation: {
    createMembership: async (_, { input }) => {
      try {
        const { user_id, points = 0 } = input;
        
        // Validasi user_id exists
        const userExists = await client.query({
          query: gql`
            query UserExists($id: Int!) {
              user(id: $id) {
                id
              }
            }
          `,
          variables: { id: user_id }
        });
        
        if (!userExists.data.user) {
          throw new Error('User not found');
        }

        const [result] = await db.query(
          'INSERT INTO memberships (user_id, points) VALUES (?, ?)',
          [user_id, points]
        );

        const [rows] = await db.query(
          'SELECT * FROM memberships WHERE id = ?', 
          [result.insertId]
        );

        // Fetch complete user data
        const membership = rows[0];
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
        return membership;

      } catch (error) {
        console.error('Create membership error:', error);
        throw new Error(error.message.includes('Network error') 
          ? 'Failed to verify user: Service unavailable' 
          : error.message);
      }
    },

    updateMembership: async (_, { id, input }) => {
      try {
        const { user_id, points } = input;
        
        // Check if membership exists
        const [existing] = await db.query(
          'SELECT * FROM memberships WHERE id = ?', 
          [id]
        );
        
        if (!existing.length) {
          throw new Error('Membership not found');
        }

        // Validate user exists if user_id is being changed
        if (user_id && user_id !== existing[0].user_id) {
          const userExists = await client.query({
            query: gql`
              query UserExists($id: Int!) {
                user(id: $id) {
                  id
                }
              }
            `,
            variables: { id: user_id }
          });
          
          if (!userExists.data.user) {
            throw new Error('New user not found');
          }
        }

        await db.query(
          'UPDATE memberships SET user_id = ?, points = ? WHERE id = ?',
          [user_id || existing[0].user_id, points || existing[0].points, id]
        );

        const [updated] = await db.query(
          'SELECT * FROM memberships WHERE id = ?', 
          [id]
        );

        // Fetch complete user data
        const membership = updated[0];
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
        return membership;

      } catch (error) {
        console.error('Update membership error:', error);
        throw new Error(error.message);
      }
    },

    deleteMembership: async (_, { id }) => {
      try {
        // Check if exists first
        const [existing] = await db.query(
          'SELECT * FROM memberships WHERE id = ?', 
          [id]
        );
        
        if (!existing.length) {
          throw new Error('Membership not found');
        }

        const [result] = await db.query(
          'DELETE FROM memberships WHERE id = ?', 
          [id]
        );
        
        return result.affectedRows > 0;

      } catch (error) {
        console.error('Delete membership error:', error);
        throw new Error(error.message);
      }
    }
  },

  Membership: {
    user: async (parent) => {
      try {
        const { data } = await client.query({
          query: gql`
            query GetUser($id: ID!) {
              user(id: $id) {
                id
                name
                phone
              }
            }
          `,
          variables: { id: parent.user_id },
        });
        return data.user;
      } catch (error) {
        console.error('Error fetching user:', error);
        return null;
      }
    }
  }
};

module.exports = resolvers;
