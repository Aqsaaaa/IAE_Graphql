const mysql = require('mysql2/promise');

const pool = mysql.createPool({
  host: 'vendor_requests-db',
  user: 'root',
  password: 'root',
  database: 'vendor_requests_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

module.exports = pool;
