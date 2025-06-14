# Vendor Requests Service - GraphQL API Documentation

# Query
## Get vendor by id

```
{
  getVendorRequest(id:1){
    status
    id
    ingredient_id
    estimated_arrival
    requested_at
  }
}
```

## Get all vendor request

```
{
  getAllVendorRequests{
    status
    id
    ingredient_id
    estimated_arrival
    requested_at
  }
}
```

# Mutations

- createVendorRequest(input: VendorRequestInput!): VendorRequest  
  Create a new vendor request record.  
  Example mutation:
  ```
  mutation {
    createVendorRequest(input: {
      vendor_id: 1,
      ingredient_id: 2,
      quantity: 100,
      status: "requested",
      requested_at: "2025-05-22T14:00:00Z",
      estimated_arrival: "2025-05-25T14:00:00Z"
    }) {
      id
      vendor_id
      ingredient_id
      quantity
      status
      requested_at
      estimated_arrival
    }
  }
  ```

- updateVendorRequest(id: ID!, input: VendorRequestInput!): VendorRequest  
  Update an existing vendor request record.  
  Example mutation:
  ```
  mutation {
    updateVendorRequest(id: 1, input: {
      status: "on-delivery"
    }) {
      id
      status
    }
  }
  ```

- deleteVendorRequest(id: ID!): Boolean  
  Delete a vendor request record by ID.  
  Example mutation:
  ```
  mutation {
    deleteVendorRequest(id: 1)
  }
  ```

## Input Types

VendorRequestInput:
- vendor_id: Int!
- ingredient_id: Int!
- quantity: Int!
- status: String
- requested_at: String
- estimated_arrival: String
