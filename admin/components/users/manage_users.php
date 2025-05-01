<div class="bg-white p-10 w-full">
    <h2 class="text-2xl font-bold mb-4">Manage Users</h2>
    <table class="w-full table-auto">
        <thead>
            <tr>
                <th class="px-4 py-2">User ID</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2"></th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <!-- User rows will be dynamically inserted here -->
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 text-center">1</td>
                <td class="px-4 py-2 text-center">
                    John Doe
                </td>
                <td class="px-4 py-2 text-center">
                    <select name="userStatus"
                        class="bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="banned">Banned</option>
                    </select>
                </td>
            </tr>

        </tbody>
    </table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>