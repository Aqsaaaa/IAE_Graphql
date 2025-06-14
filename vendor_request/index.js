const express = require('express');
const { graphqlHTTP } = require('express-graphql');
const schema = require('./schema');
const resolvers = require('./resolvers');

const app = express();

app.use('/graphql', graphqlHTTP({
  schema: schema,
  rootValue: resolvers,
  graphiql: true,
}));

const PORT = 4003;
app.listen(PORT, () => {
  console.log(`Vendor Requests service running on port ${PORT}`);
});
