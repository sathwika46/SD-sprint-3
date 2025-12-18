// import express from "express";
// import path from "path";
// import bodyParser from "body-parser";
// import bookingsRouter from "./routes/bookings.js";
// import carsRouter from "./routes/cars.js";
// import customersRouter from "./routes/customers.js";

// const app = express();

// app.set("view engine", "pug");
// app.set("views", path.join(process.cwd(), "src/views"));

// app.use(express.static(path.join(process.cwd(), "src/public")));
// app.use(bodyParser.urlencoded({ extended: true }));

// app.use("/bookings", bookingsRouter);
// app.use("/cars", carsRouter);
// app.use("/customers", customersRouter);

// app.get("/", (req, res) => res.redirect("/bookings"));

// app.listen(3000, () => console.log("Server running → http://localhost:3000"));

import express from "express";
import path from "path";
import bodyParser from "body-parser";
import session from "express-session";

import bookingsRouter from "./routes/bookings.js";
import carsRouter from "./routes/cars.js";
import customersRouter from "./routes/customers.js";
import authRouter from "./routes/auth.js"; // ✅ new authentication routes

const app = express();

// View engine setup
app.set("view engine", "pug");
app.set("views", path.join(process.cwd(), "src/views"));

// Middleware
app.use(express.static(path.join(process.cwd(), "src/public")));
app.use(bodyParser.urlencoded({ extended: true }));

// ✅ Session setup
app.use(
  session({
    secret: "your_secret_key", // replace with a strong secret
    resave: false,
    saveUninitialized: false,
    cookie: { secure: false },
  })
);

// ✅ Make session available in PUG templates
app.use((req, res, next) => {
  res.locals.user = req.session.user;
  next();
});

// Routers
app.use("/bookings", bookingsRouter);
app.use("/cars", carsRouter);
app.use("/customers", customersRouter);
app.use("/", authRouter); // ✅ added login/signup/logout routes

// Default route
app.get("/", (req, res) => {
  if (req.session.user) {
    res.redirect("/bookings"); // if logged in, go to bookings
  } else {
    res.redirect("/login"); // if not logged in, go to login
  }
});

// Server start
app.listen(3000, () => console.log("Server running → http://localhost:3000"));
