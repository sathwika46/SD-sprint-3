// import mysql from "mysql2/promise";
// import { dbConfig } from "../config/dbConfig.js";

// export const pool = mysql.createPool({
//   ...dbConfig,          // spread host, user, password, database
//   waitForConnections: true,
//   connectionLimit: 10,
//   queueLimit: 0,
// });
// src/db.js
import mysql from "mysql2/promise";

export const pool = mysql.createPool({
  host: "localhost",
  user: "root",
  password: ".", // 🔹 
  database: "car_rental",
});
