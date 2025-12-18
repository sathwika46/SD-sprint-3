import { pool } from "../db.js";

export async function getAllBookings() {
  const [rows] = await pool.query(`
    SELECT 
      b.id, cu.name AS customer_name, ca.name AS car_name, ca.model,
      b.start_date, b.end_date, b.total_price
    FROM bookings b
    JOIN customers cu ON b.customer_id = cu.id
    JOIN cars ca ON b.car_id = ca.id
  `);
  return rows;
}

export async function getBookingById(id) {
  const [rows] = await pool.query("SELECT * FROM bookings WHERE id=?", [id]);
  return rows[0];
}

export async function addBooking(car_id, customer_id, start_date, end_date, total_price) {
  await pool.query(
    "INSERT INTO bookings (car_id, customer_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)",
    [car_id, customer_id, start_date, end_date, total_price]
  );
}

export async function updateBooking(id, car_id, customer_id, start_date, end_date, total_price) {
  await pool.query(
    "UPDATE bookings SET car_id=?, customer_id=?, start_date=?, end_date=?, total_price=? WHERE id=?",
    [car_id, customer_id, start_date, end_date, total_price, id]
  );
}

export async function deleteBooking(id) {
  await pool.query("DELETE FROM bookings WHERE id=?", [id]);
}
