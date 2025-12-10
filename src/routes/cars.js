import express from "express";
import {
  getAllCars,
  getCarById,
  addCar,
  updateCar,
  deleteCar,
} from "../models/Car.js";

const router = express.Router();

// ✅ View all cars
router.get("/", async (req, res) => {
  const cars = await getAllCars();
  res.render("cars/index", { cars });
});

// ✅ Add new car form
router.get("/new", (req, res) => {
  res.render("cars/form", { car: null });
});

// ✅ Handle new car submission
router.post("/", async (req, res) => {
  const { name, model, price_per_day, available } = req.body;
  await addCar(name, model, price_per_day, available ? 1 : 0);
  res.redirect("/cars");
});

// ✅ Edit car form
router.get("/edit/:id", async (req, res) => {
  const car = await getCarById(req.params.id);
  res.render("cars/form", { car });
});

// ✅ Handle car update
router.post("/edit/:id", async (req, res) => {
  const { name, model, price_per_day, available } = req.body;
  await updateCar(req.params.id, name, model, price_per_day, available ? 1 : 0);
  res.redirect("/cars");
});

// ✅ Delete car
router.post("/delete/:id", async (req, res) => {
  await deleteCar(req.params.id);
  res.redirect("/cars");
});

export default router;
