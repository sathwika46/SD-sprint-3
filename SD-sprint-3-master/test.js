import mysql from "mysql2/promise";

async function test() {
  const connection = await mysql.createConnection({
    host: "localhost",
    user: "root",
    password: ".",
    database: "car_rental"
  });
  const [rows] = await connection.query("SHOW TABLES;");
  console.log(rows);
  await connection.end();
}

test();


