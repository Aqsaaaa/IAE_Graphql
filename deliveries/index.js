const express = require('express');
const { graphqlHTTP } = require('express-graphql');
const schema = require('./schema');
const resolvers = require('./resolvers');
const { buildSchema } = require('graphql');
const db = require('./db');

const app = express();

app.use('/graphql', graphqlHTTP({
  schema: schema,
  rootValue: resolvers,
  graphiql: true,
}));

const PORT = 4001;
app.listen(PORT, () => {
  console.log(`Deliveries service running on port ${PORT}`);
});
