const { buildSchema } = require('graphql');

const schema = buildSchema(`
  enum DeliveryStatus {
    assigned
    on_the_way
    delivered
  }

  type Delivery {
    id: ID!
    order_id: Int!
    delivery_status: DeliveryStatus!
    delivery_time: String
    current_location: String
  }

  input DeliveryInput {
    order_id: Int!
    delivery_status: DeliveryStatus
    delivery_time: String
    current_location: String
  }

  type Query {
    getDelivery(id: ID!): Delivery
    getAllDeliveries: [Delivery]
  }

  type Mutation {
    createDelivery(input: DeliveryInput!): Delivery
    updateDelivery(id: ID!, input: DeliveryInput!): Delivery
    deleteDelivery(id: ID!): Boolean
  }
`);

module.exports = schema;
