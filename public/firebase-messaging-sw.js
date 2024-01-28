importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyAZdQhSGpMQWVwBmihSUHtP_OyPk-FzbSo",
    authDomain: "taxiapp-9ce42.firebaseapp.com",
    projectId: "taxiapp-9ce42",
    storageBucket: "taxiapp-9ce42.appspot.com",
    messagingSenderId: "880105044558",
    appId: "1:880105044558:web:50c47b8e44e6c9971d611f"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
