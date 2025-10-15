// import express from "express";
// import { getAllCars, addCar } from "../models/Car.js";

// const router = express.Router();

// router.get("/", async (req, res) => {
//   const cars = await getAllCars();
//   res.render("cars/index", { title: "Cars", cars });
// });

// router.post("/", async (req, res) => {
//   await addCar(req.body);
//   res.redirect("/cars");
// });

// export default router;
import express from "express";
import { 
  getAllCars, 
  getCarById, 
  addCar, 
  updateCar, 
  deleteCar 
} from "../models/Car.js";

const router = express.Router();

// Show all cars
router.get("/", async (req, res) => {
  const cars = await getAllCars();
  res.render("cars/index", { title: "Cars", cars });
});

// Show add car form
router.get("/add", (req, res) => {
  res.render("cars/add");
});

// Add a new car
router.post("/add", async (req, res) => {
  await addCar(req.body);
  res.redirect("/cars");
});

// Show edit car form
router.get("/edit/:id", async (req, res) => {
  const car = await getCarById(req.params.id);
  res.render("cars/edit", { car });
});

// Update car
router.post("/edit/:id", async (req, res) => {
  await updateCar(req.params.id, req.body);
  res.redirect("/cars");
});

// Delete car
router.post("/delete/:id", async (req, res) => {
  await deleteCar(req.params.id);
  res.redirect("/cars");
});

export default router;
