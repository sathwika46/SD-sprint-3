import express from "express";
import {
  getAllCustomers,
  getCustomerById,
  addCustomer,
  updateCustomer,
  deleteCustomer,
} from "../models/Customer.js";

const router = express.Router();

// ✅ View all customers
router.get("/", async (req, res) => {
  const customers = await getAllCustomers();
  res.render("customers/index", { customers });
});

// ✅ Add new customer form
router.get("/new", (req, res) => {
  res.render("customers/form", { customer: null });
});

// ✅ Handle new customer submission
router.post("/", async (req, res) => {
  const { name, email, phone } = req.body;
  await addCustomer(name, email, phone);
  res.redirect("/customers");
});

// ✅ Edit customer form
router.get("/edit/:id", async (req, res) => {
  const customer = await getCustomerById(req.params.id);
  res.render("customers/form", { customer });
});

// ✅ Handle update
router.post("/edit/:id", async (req, res) => {
  const { name, email, phone } = req.body;
  await updateCustomer(req.params.id, name, email, phone);
  res.redirect("/customers");
});

// ✅ Delete customer
router.post("/delete/:id", async (req, res) => {
  await deleteCustomer(req.params.id);
  res.redirect("/customers");
});

export default router;
