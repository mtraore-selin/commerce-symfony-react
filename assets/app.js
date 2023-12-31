/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css'

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';


import React from 'react'
import { createRoot } from 'react-dom/client';


import App from './frontend/App';



const container = document.getElementById('root');
const root = createRoot(container); 
root.render(
  <React.StrictMode>
      <App />
  </React.StrictMode>,
);