
import { pool } from "../db.js";

// Get all bookings
export async function getAllBookings() {
  const [rows] = await pool.query(`
    SELECT 
      b.id, 
      ca.name AS car_name, 
      ca.model, 
      cu.name AS customer_name, 
      b.start_date, 
      b.end_date, 
      b.total_price
    FROM bookings b
    JOIN cars ca ON b.car_id = ca.id
    JOIN customers cu ON b.customer_id = cu.id
  `);
  return rows;
}

// Get booking by ID
export async function getBookingById(id) {
  const [rows] = await pool.query(
    `
    SELECT 
      b.id, 
      ca.name AS car_name, 
      cu.name AS customer_name, 
      b.start_date, 
      b.end_date, 
      b.total_price
    FROM bookings b
    JOIN cars ca ON b.car_id = ca.id
    JOIN customers cu ON b.customer_id = cu.id
    WHERE b.id = ?
    `,
    [id]
  );
  return rows[0];
}

// Add a new booking
export async function addBooking(car_id, customer_id, start_date, end_date, total_price) {
  const [result] = await pool.query(
    "INSERT INTO bookings (car_id, customer_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)",
    [car_id, customer_id, start_date, end_date, total_price]
  );
  return result.insertId;
}

// Update a booking
export async function updateBooking(id, car_id, customer_id, start_date, end_date, total_price) {
  const [result] = await pool.query(
    "UPDATE bookings SET car_id=?, customer_id=?, start_date=?, end_date=?, total_price=? WHERE id=?",
    [car_id, customer_id, start_date, end_date, total_price, id]
  );
  return result.affectedRows;
}

// Delete a booking
export async function deleteBooking(id) {
  const [result] = await pool.query("DELETE FROM bookings WHERE id = ?", [id]);
  return result.affectedRows;
}
