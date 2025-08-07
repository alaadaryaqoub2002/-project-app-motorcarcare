// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, addDoc, query, where, getDocs } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyC2KfKctrAoVzCBQBjAqXQw1Uc_1_V8i5U",
    authDomain: "carcare-app-52eb1.firebaseapp.com",
    databaseURL: "https://carcare-app-52eb1-default-rtdb.firebaseio.com",
    projectId: "carcare-app-52eb1",
    storageBucket: "carcare-app-52eb1.firebasestorage.app",
    messagingSenderId: "732379485235",
    appId: "1:732379485235:web:37818daef5f175db0ebf46",
    measurementId: "G-MFRCW8L835"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const auth = getAuth(app);

// Helper functions
async function checkUserExists(email, username) {
    const usersRef = collection(db, 'users');
    
    // Check email
    const emailQuery = query(usersRef, where('email', '==', email));
    const emailSnapshot = await getDocs(emailQuery);
    if (!emailSnapshot.empty) {
        throw new Error('Email already exists');
    }

    // Check username
    const usernameQuery = query(usersRef, where('username', '==', username));
    const usernameSnapshot = await getDocs(usernameQuery);
    if (!usernameSnapshot.empty) {
        throw new Error('Username already exists');
    }
}

async function createUser(userData) {
    return await addDoc(collection(db, 'users'), {
        ...userData,
        created_at: new Date().toISOString()
    });
}

async function loginUser(email, password) {
    const usersRef = collection(db, 'users');
    const q = query(usersRef, where('email', '==', email));
    const querySnapshot = await getDocs(q);

    if (querySnapshot.empty) {
        throw new Error('Invalid email or password');
    }

    const userDoc = querySnapshot.docs[0];
    const userData = userDoc.data();

    if (userData.password !== password) {
        throw new Error('Invalid email or password');
    }

    return {
        userId: userDoc.id,
        ...userData
    };
}

export {
    db,
    auth,
    checkUserExists,
    createUser,
    loginUser
};
