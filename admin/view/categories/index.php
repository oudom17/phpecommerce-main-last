<?php
require_once 'view/categories/crud.php';

// Handle Delete Operation
if(isset($_POST['delete']) && isset($_POST['id'])) {
    deleteCategory($_POST['id']);
}

if(isset($_POST['save'])) {
    saveCategory($_POST);
}
$categories = getAllCategories();
?>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">Categories</h3>
            </div>
        </div>
        <div class="content-body">
            <!-- Add/Edit Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add/Edit Category</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="id" id="category_id">
                                    <div class="form-group">
                                        <label for="name">Category Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Clear</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Categories List</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($categories as $category): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($category['id']) ?></td>
                                                <td><?= htmlspecialchars($category['name']) ?></td>
                                                <td><?= htmlspecialchars($category['description']) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" 
                                                            onclick="editCategory(<?= htmlspecialchars(json_encode($category)) ?>)">
                                                        <i class="ft-edit"></i>
                                                    </button>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure?')">
                                                            <i class="ft-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editCategory(category) {
    document.getElementById('category_id').value = category.id;
    document.getElementById('name').value = category.name;
    document.getElementById('description').value = category.description;
}

function resetForm() {
    document.getElementById('category_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
}
</script> 