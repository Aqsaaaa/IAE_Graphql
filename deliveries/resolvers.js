const db = require('./db');


const resolvers = {
  getDelivery: async ({ id }) => {
    const [rows] = await db.query('SELECT * FROM deliveries WHERE id = ?', [id]);
    if (rows.length === 0) return null;
    const delivery = rows[0];
    try {
      const response = await fetch(`http://localhost:4008/graphql`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          query: `
            query GetOrder($id: ID!) {
              getOrder(id: $id) {
                id
                user_id
                status
                payment_status
                order_time
              }
            }
          `,
          variables: { id: delivery.order_id }
        }),
      });
      const json = await response.json();
      delivery.order = json.data.getOrder;
    } catch (error) {
      delivery.order = null;
    }
    return delivery;
  },

  getAllDeliveries: async () => {
    const [rows] = await db.query('SELECT * FROM deliveries');
    return rows;
  },

  createDelivery: async ({ input }) => {
    const { order_id, delivery_status = 'assigned', delivery_time = null, current_location = null } = input;
    const [result] = await db.query(
      'INSERT INTO deliveries (order_id, delivery_status, delivery_time, current_location) VALUES (?, ?, ?, ?)',
      [order_id, delivery_status, delivery_time, current_location]
    );
    const [rows] = await db.query('SELECT * FROM deliveries WHERE id = ?', [result.insertId]);
    return rows[0];
  },

  updateDelivery: async ({ id, input }) => {
    const { order_id, delivery_status, delivery_time, current_location } = input;
    await db.query(
      'UPDATE deliveries SET order_id = ?, delivery_status = ?, delivery_time = ?, current_location = ? WHERE id = ?',
      [order_id, delivery_status, delivery_time, current_location, id]
    );
    const [rows] = await db.query('SELECT * FROM deliveries WHERE id = ?', [id]);
    return rows[0];
  },

  deleteDelivery: async ({ id }) => {
    const [result] = await db.query('DELETE FROM deliveries WHERE id = ?', [id]);
    return result.affectedRows > 0;
  },
};

module.exports = resolvers;
