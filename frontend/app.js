document.addEventListener('DOMContentLoaded', () => {
    const taskForm = document.getElementById('taskForm');
    const taskList = document.getElementById('taskList');
    const API_URL = 'http://localhost/task-manager/backend/api.php';

    function createTaskElement(task) {
        const li = document.createElement('li');
        li.className = `task ${task.completed == 1 ? 'completed' : ''} ${task.prioritario == 1 ? 'priority' : ''}`;
        li.dataset.id = task.id;
        
        li.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p>${task.description || 'No description'}</p>
                <div class="task-meta">
                    ${task.vencimiento ? `<small class="task-due-date">Vence: ${new Date(task.vencimiento).toLocaleDateString()}</small>` : ''}
                    ${task.prioritario == 1 ? '<span class="priority-badge">PRIORIDAD</span>' : ''}
                </div>
            </div>
            <div class="task-actions">
                <button class="complete-btn" onclick="toggleTask(${task.id}, ${task.completed})">
                    ${task.completed == 1 ? '↩ Reabrir' : '✓ Completar'}
                </button>
                <button class="delete-btn" onclick="deleteTask(${task.id})">✕ Eliminar</button>
            </div>
        `;
        
        return li;
    }

    // Load tasks
    function loadTasks() {
        fetch(API_URL)
            .then(res => res.json())
            .then(tasks => {
                document.getElementById('pendingTasks').innerHTML = '';
                document.getElementById('priorityTasks').innerHTML = '';
                document.getElementById('completedTasks').innerHTML = '';
                
                tasks.forEach(task => {
                    const taskElement = createTaskElement(task);
                    
                    if (task.completed == 1) {
                        document.getElementById('completedTasks').appendChild(taskElement);
                    } else if (task.prioritario == 1) {
                        document.getElementById('priorityTasks').appendChild(taskElement);
                    } else {
                        document.getElementById('pendingTasks').appendChild(taskElement);
                    }
                });
            });
    }

    // Add new task
    taskForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const newTask = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            vencimiento: document.getElementById('vencimiento').value || null,
            prioritario: document.getElementById('prioritario').checked ? 1 : 0
        };

        fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newTask)
        }).then(() => {
            taskForm.reset();
            loadTasks();
        });
    });

    // Delete task
    window.deleteTask = (id) => {
        if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
            fetch(`${API_URL}?id=${id}`, { method: 'DELETE' })
                .then(() => loadTasks());
        }
    };

    // Toggle task status
    window.toggleTask = (id, currentStatus) => {
        const newStatus = currentStatus == 1 ? 0 : 1;
        fetch(API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, completed: newStatus })
        }).then(() => loadTasks());
    };

    // Edit
    window.editTask = (id) => {
        alert('Funcionalidad de edición no implementada aún. ID: ' + id);
    };

    loadTasks();
});