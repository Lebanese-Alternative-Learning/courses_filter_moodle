# Search for Courses

This block allows users to search for courses using custom fields associated with the courses.

## How to Use

After installing the plugin, you need to define the following settings:

- **Show Search Form:** This is the main form for the "Standard Search Result" page. You can determine whether to display the main search form inside this page or not.
- **PerPage:** This setting defines how many items will be shown for the search result.
- **Custom Fields:** For the "Standard Search Result" page, you can specify which fields to search by.
- You will see a setting for each custom field to define the search operation:
    - **Equal number:** against "="
    - **Equal string:** against "like ?" means the field must match the value.
    - **More than:** against ">"
    - **Less than:** against "<"
    - **Contains:** against "like %?%" means the field must contain the value.
    - **Start with:** against "like ?%" means the field must start with the value.
    - **End with:** against "like %?" means the field must end with the value.

## Custom Settings on the Block Level

The following settings are for each block instance you add:

- **Block Style:** "Side" to display one item per row, "Content" to show four items per row.
- **Submit Behavior:**
    - "Go to the Standard Search Page" means when the user clicks the search button, they will be directed to the standard search result page "search.php".
    - "Ajax request" means the result will be shown in the same block.
    - You can specify which fields to search by.

## Custom Standard Search Result Page "search.php"

- You can customize this page by category. You just need to pass "course_category" as a URL parameter, or you can search for a specific category from the list and then customize the blocks for this page.
- You can display the main form inside the Standard Search Result page from the plugin settings.
- For any "Search for Courses" block you add inside the Standard Search Result page, the result will be shown inside the main content, instead of within the same block.
