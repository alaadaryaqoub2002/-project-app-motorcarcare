<?php include 'conDB/head-company.php'; ?>

<div class="container py-4">
    <h2 class="mb-4 text-primary"><i class="fas fa-exclamation-triangle me-2"></i>Reported Issues</h2>
    <div id="issuesList"></div>
</div>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, getDocs, query, orderBy } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyC2KfKctrAoVzCBQBjAqXQw1Uc_1_V8i5U",
    authDomain: "carcare-app-52eb1.firebaseapp.com",
    databaseURL: "https://carcare-app-52eb1-default-rtdb.firebaseio.com",
    projectId: "carcare-app-52eb1",
    storageBucket: "carcare-app-52eb1.appspot.com",
    messagingSenderId: "732379485235",
    appId: "1:732379485235:web:37818daef5f175db0ebf46",
    measurementId: "G-MFRCW8L835"
};
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

function formatDate(ts) {
    if (!ts || !ts.seconds) return '';
    const date = new Date(ts.seconds * 1000);
    return date.toLocaleDateString('en-GB') + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function getStatusBadge(status) {
    switch ((status||'').toLowerCase()) {
        case 'resolved': return '<span class="badge bg-success">Resolved</span>';
        case 'in progress': return '<span class="badge bg-info">In Progress</span>';
        case 'closed': return '<span class="badge bg-danger">Closed</span>';
        case 'new': return '<span class="badge bg-primary">New</span>';
        default: return '<span class="badge bg-secondary">Unknown</span>';
    }
}
function getPriorityBadge(priority) {
    switch ((priority||'').toLowerCase()) {
        case 'high': return '<span class="badge bg-danger">High</span>';
        case 'medium': return '<span class="badge bg-warning text-dark">Medium</span>';
        case 'low': return '<span class="badge bg-success">Low</span>';
        default: return '<span class="badge bg-secondary">-</span>';
    }
}

async function loadIssues() {
    const issuesList = document.getElementById('issuesList');
    issuesList.innerHTML = '<div class="text-muted">Loading...</div>';
    try {
        const q = query(collection(db, 'reported_issues'), orderBy('created_at', 'desc'));
        const querySnapshot = await getDocs(q);
        let issues = [];
        querySnapshot.forEach(doc => {
            issues.push({id: doc.id, ...doc.data()});
        });
        if (!issues.length) {
            issuesList.innerHTML = '<div class="text-muted">No issues found.</div>';
            return;
        }
        let html = '';
        issues.forEach(issue => {
            html += `
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold text-primary">${issue.title || 'No title'}</span>
                            <span class="ms-2">${getStatusBadge(issue.status)}</span>
                            <span class="ms-2">${getPriorityBadge(issue.priority)}</span>
                        </div>
                        <span class="text-muted small">${formatDate(issue.created_at)}</span>
                    </div>
                    <div class="mb-2"><strong>Description:</strong> ${issue.description || ''}</div>
                    <div class="mb-2 text-muted small">Reported by: ${issue.user_email || ''}</div>
                    ${issue.image_url ? `<div class="mb-2"><img src="${issue.image_url}" alt="Issue Image" style="max-width:180px; max-height:120px; border-radius:8px;"></div>` : ''}
                    ${issue.company_comment ? `<div class="mb-2"><strong>Company Comment:</strong> <span class="text-primary">${issue.company_comment}</span></div>` : ''}
                </div>
            </div>
            `;
        });
        issuesList.innerHTML = html;
    } catch (error) {
        issuesList.innerHTML = '<div class="text-danger">Error loading issues.</div>';
    }
}

document.addEventListener('DOMContentLoaded', loadIssues);
</script>

<?php include 'conDB/footer.php'; ?> 