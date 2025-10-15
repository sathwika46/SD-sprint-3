
import express from "express";
import { 
  getAllCustomers, 
  getCustomerById, 
  addCustomer, 
  updateCustomer, 
  deleteCustomer 
} from "../models/Customer.js";

const router = express.Router();

// Show all customers
router.get("/", async (req, res) => {
  const customers = await getAllCustomers();
  res.render("customers/index", { title: "Customers", customers });
});

// Show add customer form
router.get("/add", (req, res) => {
  res.render("customers/add");
});

// Add a new customer
router.post("/add", async (req, res) => {
  await addCustomer(req.body);
  res.redirect("/customers");
});

// Show edit customer form
router.get("/edit/:id", async (req, res) => {
  const customer = await getCustomerById(req.params.id);
  res.render("customers/edit", { customer });
});

// Update customer
router.post("/edit/:id", async (req, res) => {
  await updateCustomer(req.params.id, req.body);
  res.redirect("/customers");
});

// Delete customer
router.post("/delete/:id", async (req, res) => {
  await deleteCustomer(req.params.id);
  res.redirect("/customers");
});

export default router;
