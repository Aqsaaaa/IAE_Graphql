# Deliveries Service - GraphQL API Documentation

## Mutations

- createDelivery(input: DeliveryInput!): Delivery  
  Create a new delivery record.  
  Example mutation:
  ```
  mutation {
    createDelivery(input: {
      order_id: 1,
      delivery_status: "assigned",
      delivery_time: "2025-05-22T14:00:00Z",
      current_location: "Warehouse"
    }) {
      id
      order_id
      delivery_status
      delivery_time
      current_location
    }
  }
  ```

- updateDelivery(id: ID!, input: DeliveryInput!): Delivery  
  Update an existing delivery record.  
  Example mutation:
  ```
  mutation {
    updateDelivery(id: 1, input: {
      delivery_status: "on the way",
      current_location: "On route"
    }) {
      id
      delivery_status
      current_location
    }
  }
  ```

- deleteDelivery(id: ID!): Boolean  
  Delete a delivery record by ID.  
  Example mutation:
  ```
  mutation {
    deleteDelivery(id: 1)
  }
  ```

## Input Types

DeliveryInput:
- order_id: Int!
- delivery_status: String
- delivery_time: String
- current_location: String
