

import { pool } from "../db.js";

// Get all customers
export async function getAllCustomers() {
  const [rows] = await pool.query("SELECT * FROM customers");
  return rows;
}

// Get customer by ID
export async function getCustomerById(id) {
  const [rows] = await pool.query("SELECT * FROM customers WHERE id = ?", [id]);
  return rows[0];
}

// Add new customer
export async function addCustomer(name, email, phone) {
  const [result] = await pool.query(
    "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)",
    [name, email, phone]
  );
  return result.insertId;
}

// Update customer
export async function updateCustomer(id, name, email, phone) {
  const [result] = await pool.query(
    "UPDATE customers SET name=?, email=?, phone=? WHERE id=?",
    [name, email, phone, id]
  );
  return result.affectedRows;
}

// Delete customer
export async function deleteCustomer(id) {
  const [result] = await pool.query("DELETE FROM customers WHERE id = ?", [id]);
  return result.affectedRows;
}
