<div id="editUserPanel" class="edit-panel">
    <form method="POST" action="index.php?action=update_user_status">
        <input type="hidden" name="user_id" id="edit_user_id">

        <label>Status</label>
        <select name="status" id="edit_user_status" class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <div class="mt-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closePanel()">Cancel</button>
        </div>
    </form>
</div>
