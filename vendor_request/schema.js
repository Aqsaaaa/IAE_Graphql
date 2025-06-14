const { buildSchema } = require('graphql');

const schema = buildSchema(`
  enum RequestStatus {
    requested
    accepted
    on_delivery
    delivered
  }

  type VendorRequest {
    id: ID!
    vendor_id: Int!
    ingredient_id: Int!
    quantity: Int!
    status: RequestStatus!
    requested_at: String
    estimated_arrival: String
  }

  input VendorRequestInput {
    vendor_id: Int!
    ingredient_id: Int!
    quantity: Int!
    status: RequestStatus
    requested_at: String
    estimated_arrival: String
  }

  type Query {
    getVendorRequest(id: ID!): VendorRequest
    getAllVendorRequests: [VendorRequest]
  }

  type Mutation {
    createVendorRequest(input: VendorRequestInput!): VendorRequest
    updateVendorRequest(id: ID!, input: VendorRequestInput!): VendorRequest
    deleteVendorRequest(id: ID!): Boolean
  }
`);

module.exports = schema;
