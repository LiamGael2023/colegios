const express = require('express');
const { body } = require('express-validator');
const { login, register, getProfile, changePassword } = require('../controllers/auth.controller');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();

router.post('/login', [
  body('email').isEmail().withMessage('Email inválido'),
  body('password').notEmpty().withMessage('Contraseña requerida')
], login);

router.post('/register', [
  authMiddleware,
  requireRole('ADMIN', 'DIRECTOR'),
  body('email').isEmail().withMessage('Email inválido'),
  body('password').isLength({ min: 6 }).withMessage('Contraseña mínimo 6 caracteres'),
  body('nombre').notEmpty().withMessage('Nombre requerido'),
  body('apellidos').notEmpty().withMessage('Apellidos requeridos'),
  body('dni').isLength({ min: 8, max: 8 }).withMessage('DNI debe tener 8 dígitos')
], register);

router.get('/profile', authMiddleware, getProfile);

router.put('/change-password', [
  authMiddleware,
  body('currentPassword').notEmpty().withMessage('Contraseña actual requerida'),
  body('newPassword').isLength({ min: 6 }).withMessage('Nueva contraseña mínimo 6 caracteres')
], changePassword);

module.exports = router;
