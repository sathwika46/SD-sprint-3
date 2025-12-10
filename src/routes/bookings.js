import express from "express";
import { getAllBookings, getBookingById, addBooking, updateBooking, deleteBooking } from "../models/Booking.js";
import { getAllCars } from "../models/Car.js";
import { getAllCustomers } from "../models/Customer.js";

const router = express.Router();

router.get("/", async (req, res) => {
  const bookings = await getAllBookings();
  res.render("bookings/index", { bookings });
});

router.get("/new", async (req, res) => {
  const cars = await getAllCars();
  const customers = await getAllCustomers();
  res.render("bookings/forms", { booking: null, cars, customers });
});

router.post("/", async (req, res) => {
  const { car_id, customer_id, start_date, end_date, total_price } = req.body;
  await addBooking(car_id, customer_id, start_date, end_date, total_price);
  res.redirect("/bookings");
});

router.get("/edit/:id", async (req, res) => {
  const booking = await getBookingById(req.params.id);
  const cars = await getAllCars();
  const customers = await getAllCustomers();
  res.render("bookings/forms", { booking, cars, customers });
});

router.post("/edit/:id", async (req, res) => {
  const { car_id, customer_id, start_date, end_date, total_price } = req.body;
  await updateBooking(req.params.id, car_id, customer_id, start_date, end_date, total_price);
  res.redirect("/bookings");
});

router.post("/delete/:id", async (req, res) => {
  await deleteBooking(req.params.id);
  res.redirect("/bookings");
});

export default router;
