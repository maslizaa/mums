/**
 * Import function triggers from their respective submodules:
 *
 * const {onCall} = require("firebase-functions/v2/https");
 * const {onDocumentWritten} = require("firebase-functions/v2/firestore");
 *
 * See a full list of supported triggers at https://firebase.google.com/docs/functions
 */

const {setGlobalOptions} = require("firebase-functions");
const {onRequest} = require("firebase-functions/https");
const logger = require("firebase-functions/logger");

// For cost control, you can set the maximum number of containers that can be
// running at the same time. This helps mitigate the impact of unexpected
// traffic spikes by instead downgrading performance. This limit is a
// per-function limit. You can override the limit for each function using the
// `maxInstances` option in the function's options, e.g.
// `onRequest({ maxInstances: 5 }, (req, res) => { ... })`.
// NOTE: setGlobalOptions does not apply to functions using the v1 API. V1
// functions should each use functions.runWith({ maxInstances: 10 }) instead.
// In the v1 API, each function can only serve one request per container, so
// this will be the maximum concurrent request count.
setGlobalOptions({ maxInstances: 10 });

// Create and deploy your first functions
// https://firebase.google.com/docs/functions/get-started

// Handle appointment booking
exports.api = onRequest(async (request, response) => {
  // Enable CORS
  response.set('Access-Control-Allow-Origin', '*');
  response.set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  response.set('Access-Control-Allow-Headers', 'Content-Type');

  // Handle preflight requests
  if (request.method === 'OPTIONS') {
    response.status(204).send('');
    return;
  }

  // Get the path from the request
  const path = request.path || request.url;

  // Handle appointment booking
  if (path.includes('/book-appointment') && request.method === 'POST') {
    try {
      const appointmentData = request.body;
      
      // Validate required fields (matching the original PHP validation)
      const requiredFields = ['full_name', 'email', 'phone', 'service', 'therapist', 'date', 'time'];
      for (const field of requiredFields) {
        if (!appointmentData[field]) {
          response.status(400).json({ error: `Missing required field: ${field}` });
          return;
        }
      }

      // Validate phone number format (matching original PHP validation)
      const phone = appointmentData.phone.replace(/\s/g, '');
      if (!/^0\d{9,10}$/.test(phone)) {
        response.status(400).json({ 
          error: 'Nombor telefon mesti bermula dengan 0 dan mengandungi 10 atau 11 digit.' 
        });
        return;
      }

      // Normalize phone number to local format (matching original PHP logic)
      let normalizedPhone = phone;
      if (phone.startsWith('+60')) {
        normalizedPhone = '0' + phone.substring(3);
      } else if (phone.startsWith('60')) {
        normalizedPhone = '0' + phone.substring(2);
      }

      // Create appointment object
      const appointment = {
        appointment_id: Date.now().toString(),
        customer_name: appointmentData.full_name,
        customer_email: appointmentData.email,
        customer_phone: normalizedPhone,
        service_id: appointmentData.service,
        therapist_id: appointmentData.therapist,
        date: appointmentData.date,
        time: appointmentData.time,
        addon_therapist: appointmentData.addon_therapist || null,
        addon_service: appointmentData.addon_service || null,
        status: 'confirmed',
        created_at: new Date().toISOString()
      };

      // Here you would typically save to a database (Firestore)
      // For now, we'll just log the appointment
      logger.info('New appointment booking:', appointment);

      // Send confirmation email (you can implement this later)
      // await sendConfirmationEmail(appointment);

      response.status(200).json({
        success: true,
        message: 'Appointment booked successfully',
        appointment: appointment
      });

    } catch (error) {
      logger.error('Error booking appointment:', error);
      response.status(500).json({ error: 'Internal server error' });
    }
  } else {
    response.status(404).json({ error: 'Endpoint not found', path: path });
  }
});

// Example function for sending confirmation emails
// You can implement this using a service like SendGrid or Nodemailer
async function sendConfirmationEmail(appointmentData) {
  // Implementation for sending confirmation emails
  // This would replace your PHP mailer functionality
  logger.info('Sending confirmation email to:', appointmentData.customer_email);
}
