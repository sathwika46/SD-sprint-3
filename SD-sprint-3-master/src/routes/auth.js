import express from 'express';
import bcrypt from 'bcrypt';
import { createUser, findUserByEmail } from '../models/User.js';

const router = express.Router();

// Signup page
router.get('/signup', (req, res) => res.render('auth/signup', { user: req.session.user }));

router.post('/signup', async (req, res) => {
  const { name, email, password } = req.body;
  try {
    await createUser(name, email, password);
    res.redirect('/login');
  } catch (err) {
    console.error(err);
    res.send('Signup failed. Try again.');
  }
});

// Login page
router.get('/login', (req, res) => res.render('auth/login', { user: req.session.user }));

router.post('/login', async (req, res) => {
  const { email, password } = req.body;
  const user = await findUserByEmail(email);
  if (!user) return res.send('User not found');

  const match = await bcrypt.compare(password, user.password);
  if (!match) return res.send('Incorrect password');

  req.session.user = { id: user.id, name: user.name, role: user.role };
  res.redirect('/bookings');
});

// Logout
router.get('/logout', (req, res) => {
  req.session.destroy(() => res.redirect('/login'));
});

export default router;
