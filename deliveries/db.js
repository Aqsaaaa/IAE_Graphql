const mysql = require('mysql2/promise');

const pool = mysql.createPool({
  host: 'deliveries-db',
  user: 'root',
  password: 'root',
  database: 'deliveries_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

module.exports = pool;
