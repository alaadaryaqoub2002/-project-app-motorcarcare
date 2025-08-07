<?php include 'conDB/head-company.php'; ?>

<div class="content-wrapper" style="margin-top: 50px;">
    <div class="container my-5">
        <br><br><br>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-0" style="font-size: 24px;">Manage Services</h1>
                <div id="serviceCount" class="text-muted mt-1"></div>
            </div>
            <a href="add_service.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Add New Service
            </a>
        </div>

        <!-- Services Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Description</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="servicesTableBody">
                            <!-- Services will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Service Modal -->
<div class="modal fade" id="updateServiceModal" tabindex="-1" aria-labelledby="updateServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateServiceModalLabel">Update Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateServiceForm">
                    <input type="hidden" id="serviceId">
                    <div class="mb-3">
                        <label for="updateName" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="updateName" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="updateDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="updateDuration" class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control" id="updateDuration" required>
                    </div>
                    <div class="mb-3">
                        <label for="updatePrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="updatePrice" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateStatus" class="form-label">Status</label>
                        <select class="form-select" id="updateStatus" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="updateImage" class="form-label">New Image (Optional)</label>
                        <input type="file" class="form-control" id="updateImage" accept="image/*">
                        <small class="form-text text-muted">Maximum file size: 5MB. Supported formats: JPG, PNG</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveServiceChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, getDocs, doc, updateDoc, serverTimestamp, deleteDoc, doc as firestoreDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { getStorage, ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-storage.js";

// Firebase configuration
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
const storage = getStorage(app);

// Format timestamp
function formatDate(timestamp) {
    if (!timestamp) return 'N/A';
    
    try {
        const date = timestamp.toDate();
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        console.error('Error formatting date:', error);
        return 'N/A';
    }
}

// Format price
function formatPrice(price) {
    const numPrice = Number(price) || 0;
    return numPrice.toFixed(2);
}

// Restore the original loadServices function and remove the Delete button
async function loadServices() {
    try {
        const servicesRef = collection(db, 'services');
        const querySnapshot = await getDocs(servicesRef);
        
        const tableBody = document.getElementById('servicesTableBody');
        const serviceCountElement = document.getElementById('serviceCount');
        tableBody.innerHTML = '';
        
        if (querySnapshot.empty) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">No services found</td>
                </tr>
            `;
            if (serviceCountElement) {
                serviceCountElement.textContent = 'No services found';
            }
            return;
        }
        
        // Convert to array for sorting
        const services = [];
        querySnapshot.forEach(doc => {
            services.push({
                id: doc.id,
                ...doc.data()
            });
        });
        
        // Sort by created_at (newest first)
        services.sort((a, b) => {
            const dateA = a.created_at ? a.created_at.toDate() : new Date(0);
            const dateB = b.created_at ? b.created_at.toDate() : new Date(0);
            return dateB - dateA;
        });
        
        // Create table rows
        services.forEach(service => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        ${service.image_url ? 
                            `<img src="${service.image_url}" class="rounded me-2" style="width: 28px; height: 28px; object-fit: cover;">` : 
                            '<div class="bg-light rounded me-2" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image text-muted"></i></div>'
                        }
                        <div>
                            <div class="fw-bold">${service.name || 'Unnamed Service'}</div>
                        </div>
                    </div>
                </td>
                <td><div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${service.description || 'No description'}</div></td>
                <td>${parseInt(service.duration) || 0} mins</td>
                <td>$${formatPrice(service.price)}</td>
                <td>
                    <span class="badge ${service.status === 'active' ? 'bg-success' : 'bg-danger'}">
                        ${service.status || 'active'}
                    </span>
                </td>
                <td>${formatDate(service.updated_at)}</td>
                <td>
                    <button class="btn btn-sm btn-primary me-2" onclick="window.editService('${service.id}', ${JSON.stringify({
                        name: service.name || '',
                        description: service.description || '',
                        duration: parseInt(service.duration) || 0,
                        price: Number(service.price) || 0,
                        status: service.status || 'active'
                    }).replace(/"/g, '&quot;')})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="window.deleteService('${service.id}', '${service.name || ''}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        // Update total count
        if (serviceCountElement) {
            serviceCountElement.textContent = `Total Services: ${services.length}`;
        }
        
    } catch (error) {
        console.error('Error loading services:', error);
        alert('Error loading services. Please try refreshing the page.');
    }
}

// Edit service
window.editService = function(serviceId, service) {
    document.getElementById('serviceId').value = serviceId;
    document.getElementById('updateName').value = service.name;
    document.getElementById('updateDescription').value = service.description;
    document.getElementById('updateDuration').value = service.duration;
    document.getElementById('updatePrice').value = service.price;
    document.getElementById('updateStatus').value = service.status || 'active';
    
    $('#updateServiceModal').modal('show');
};

// Handle service update
document.getElementById('saveServiceChanges').addEventListener('click', async function() {
    try {
        const serviceId = document.getElementById('serviceId').value;
        const imageFile = document.getElementById('updateImage').files[0];
        
        let updateData = {
            name: document.getElementById('updateName').value.trim(),
            description: document.getElementById('updateDescription').value.trim(),
            duration: parseInt(document.getElementById('updateDuration').value),
            price: parseFloat(document.getElementById('updatePrice').value),
            status: document.getElementById('updateStatus').value,
            updated_at: serverTimestamp()
        };
        
        // Handle image upload if new image is selected
        if (imageFile) {
            if (imageFile.size > 5 * 1024 * 1024) {
                throw new Error('Image size should not exceed 5MB');
            }
            
            if (!['image/jpeg', 'image/png'].includes(imageFile.type)) {
                throw new Error('Only JPG and PNG images are allowed');
            }
            
            const filename = `services/${Date.now()}_${imageFile.name}`;
            const storageRef = ref(storage, filename);
            const snapshot = await uploadBytes(storageRef, imageFile);
            updateData.image_url = await getDownloadURL(snapshot.ref);
        }
        
        // Update service in Firestore
        const serviceRef = doc(db, 'services', serviceId);
        await updateDoc(serviceRef, updateData);
        
        // Hide modal and reload services
        $('#updateServiceModal').modal('hide');
        loadServices();
        alert('Service updated successfully!');
        
    } catch (error) {
        console.error('Error updating service:', error);
        alert(error.message || 'Error updating service. Please try again.');
    }
});

// Add this after window.editService
window.deleteService = async function(serviceId, serviceName) {
    if (!confirm(`Are you sure you want to delete the service "${serviceName}"? This action cannot be undone.`)) {
        return;
    }
    try {
        await deleteDoc(doc(db, 'services', serviceId));
        alert('Service deleted successfully!');
        loadServices();
    } catch (error) {
        console.error('Error deleting service:', error);
        alert(error.message || 'Error deleting service. Please try again.');
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', loadServices);
</script>

<?php include 'conDB/footer.php'; ?>
