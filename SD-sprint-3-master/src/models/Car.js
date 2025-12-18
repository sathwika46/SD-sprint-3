import { pool } from "../db.js";

export async function getAllCars() {
  const [rows] = await pool.query("SELECT * FROM cars");
  return rows;
}

export async function getCarById(id) {
  const [rows] = await pool.query("SELECT * FROM cars WHERE id = ?", [id]);
  return rows[0];
}

export async function addCar(name, model, price_per_day, available) {
  const [result] = await pool.query(
    "INSERT INTO cars (name, model, price_per_day, available) VALUES (?, ?, ?, ?)",
    [name, model, price_per_day, available]
  );
  return result.insertId;
}

export async function updateCar(id, name, model, price_per_day, available) {
  await pool.query(
    "UPDATE cars SET name=?, model=?, price_per_day=?, available=? WHERE id=?",
    [name, model, price_per_day, available, id]
  );
}

export async function deleteCar(id) {
  await pool.query("DELETE FROM cars WHERE id=?", [id]);
}
