import { pool } from "../db.js";

export async function getAllCustomers() {
  const [rows] = await pool.query("SELECT * FROM customers");
  return rows;
}

export async function getCustomerById(id) {
  const [rows] = await pool.query("SELECT * FROM customers WHERE id=?", [id]);
  return rows[0];
}

export async function addCustomer(name, email, phone) {
  const [result] = await pool.query(
    "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)",
    [name, email, phone]
  );
  return result.insertId;
}

export async function updateCustomer(id, name, email, phone) {
  await pool.query(
    "UPDATE customers SET name=?, email=?, phone=? WHERE id=?",
    [name, email, phone, id]
  );
}

export async function deleteCustomer(id) {
  await pool.query("DELETE FROM customers WHERE id=?", [id]);
}
