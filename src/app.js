import express from "express";
import path from "path";
import bodyParser from "body-parser";
import bookingsRouter from "./routes/bookings.js";
import carsRouter from "./routes/cars.js";
import customersRouter from "./routes/customers.js";

const app = express();

app.set("view engine", "pug");
app.set("views", path.join(process.cwd(), "src/views"));

app.use(express.static(path.join(process.cwd(), "src/public")));
app.use(bodyParser.urlencoded({ extended: true }));

app.use("/bookings", bookingsRouter);
app.use("/cars", carsRouter);
app.use("/customers", customersRouter);

app.get("/", (req, res) => res.redirect("/bookings"));

app.listen(3000, () => console.log("Server running → http://localhost:3000"));
