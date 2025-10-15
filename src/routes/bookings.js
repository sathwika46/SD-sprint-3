

import express from "express";
import { getAllBookings, getBookingById, addBooking, updateBooking, deleteBooking } from "../models/Booking.js";
import { getAllCustomers } from "../models/Customer.js";
import { getAllCars } from "../models/Car.js";

const router = express.Router();

// List
router.get("/", async (req, res) => {
  const bookings = await getAllBookings();
  res.render("bookings/index", { bookings });
});

// Add form
router.get("/new", async (req, res) => {
  const customers = await getAllCustomers();
  const cars = await getAllCars();
  res.render("bookings/form", { customers, cars });
});

// Add submit
router.post("/", async (req, res) => {
  await addBooking(req.body);
  res.redirect("/bookings");
});

// Edit form
router.get("/edit/:id", async (req, res) => {
  const booking = await getBookingById(req.params.id);
  const customers = await getAllCustomers();
  const cars = await getAllCars();
  res.render("bookings/form", { booking, customers, cars });
});

// Edit submit
router.post("/edit/:id", async (req, res) => {
  await updateBooking(req.params.id, req.body);
  res.redirect("/bookings");
});

// Delete
router.post("/delete/:id", async (req, res) => {
  await deleteBooking(req.params.id);
  res.redirect("/bookings");
});

export default router;
