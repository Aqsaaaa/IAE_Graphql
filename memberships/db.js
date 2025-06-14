const mysql = require('mysql2/promise');

const pool = mysql.createPool({
  host: 'memberships-db',
  user: 'root',
  password: 'root',
  database: 'memberships_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

module.exports = pool;
