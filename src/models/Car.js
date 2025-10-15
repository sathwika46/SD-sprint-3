

import { pool } from "../db.js";

// Get all cars
export async function getAllCars() {
  const [rows] = await pool.query("SELECT * FROM cars");
  return rows;
}

// Get a car by ID
export async function getCarById(id) {
  const [rows] = await pool.query("SELECT * FROM cars WHERE id = ?", [id]);
  return rows[0];
}

// Add a new car
export async function addCar(name, model, price_per_day, status) {
  const [result] = await pool.query(
    "INSERT INTO cars (name, model, price_per_day, status) VALUES (?, ?, ?, ?)",
    [name, model, price_per_day, status]
  );
  return result.insertId;
}

// Update a car
export async function updateCar(id, name, model, price_per_day, status) {
  const [result] = await pool.query(
    "UPDATE cars SET name=?, model=?, price_per_day=?, status=? WHERE id=?",
    [name, model, price_per_day, status, id]
  );
  return result.affectedRows;
}

// Delete a car
export async function deleteCar(id) {
  const [result] = await pool.query("DELETE FROM cars WHERE id = ?", [id]);
  return result.affectedRows;
}
