# Search for Courses

## What is it?

This block to make the user are able to search for courses by the course custom fields.

## How to user?

After install the plugins you need to define the following settings:

* Show Search From: this is the main form for the "Standard Search Result" page, you can determine, if you need to print the main search form inside this page or not.
* PerPage: this setting to define how many items will be shown for the search result.
* Custom fields: for the "Standard Search Result" page, you can specify which fields to search by.
* You will see a setting for each custom field to define the operation search:
    - Equal number: against "="
    - Equal string: against " like ? " means the field must be matched the value.
    - More than: against ">"
    - Less than: against "<"
    - Contains: against " like %?%" any the field must contain the value.
    - Start with: against " like ?%" any the field must start with the value.
    - End with: against " like ?%" any the field must end with the value.

## Custom Settings on the block level

The following settings for each block instance you added:

* Block Style: "Side" to display one item per row, "Content" four items will be shown per row.
* Submit Behavior:
    - "Go to the Standard Search Page" means when the user clicks on search button he will be moved to the standard search result page "search.php"
    - "Ajax request" means the result will be shown in the same block.
    - You can specify which fields to search by.

## Custom Standard Search Result page "search.php"

* You can customize this page by the category, you just need to pass "course_category" as url parameter, or you can search for specific category from the list, and then customize
  the blocks for this page.
* You can display the main form inside the Standard Search Result page from the plugin settings.
* For any "Search for Courses" block you added inside the Standard Search Result page, the result will be shown inside the main content, instead of the same block.
