# NetSuite RESTlet Deployment Guide

This directory contains NetSuite RESTlet scripts for the Book Management demo.

## Files

- `BookManagementRESTlet.js` - Main RESTlet script for book CRUD operations

## Deployment Steps

### 1. Create Custom Fields in NetSuite

Before deploying the RESTlet, create these custom fields on the Item record:

1. **ISBN** (custitem_isbn)
   - Type: Free-Form Text
   - Label: ISBN
   - Max Length: 17

2. **Author** (custitem_author)
   - Type: Free-Form Text
   - Label: Author
   - Max Length: 255

3. **Publisher** (custitem_publisher)
   - Type: Free-Form Text
   - Label: Publisher
   - Max Length: 255

4. **Genre** (custitem_genre)
   - Type: List/Record
   - Label: Genre
   - Options: Fiction, Non-Fiction, Science Fiction, Mystery, Romance, Biography, History, Self-Help

5. **Pages** (custitem_pages)
   - Type: Integer Number
   - Label: Pages

6. **Language** (custitem_language)
   - Type: List/Record
   - Label: Language
   - Options: English, Spanish, French, German, Italian

7. **Published Date** (custitem_published_date)
   - Type: Date
   - Label: Published Date

8. **Stock** (custitem_stock)
   - Type: Integer Number
   - Label: Stock

### 2. Deploy the RESTlet

1. Go to **Customization > Scripting > Scripts > New**
2. Choose **RESTlet** as the script type
3. Copy the contents of `BookManagementRESTlet.js` into the script editor
4. Save the script and note the **Script ID**

### 3. Create Script Deployment

1. Go to **Customization > Scripting > Script Deployments > New**
2. Select your RESTlet script
3. Set the deployment title: "Book Management RESTlet"
4. Set the status to **Released**
5. Save the deployment and note the **Deployment ID**

### 4. Configure Laravel Environment

Add these variables to your `.env` file:

```env
# NetSuite RESTlet Configuration
NETSUITE_RESTLET_URL=https://YOUR_ACCOUNT.restlets.api.netsuite.com/app/site/hosting/restlet.nl?script=YOUR_SCRIPT_ID&deploy=YOUR_DEPLOY_ID
NETSUITE_SCRIPT_ID=YOUR_SCRIPT_ID
NETSUITE_DEPLOY_ID=YOUR_DEPLOY_ID
NETSUITE_AUTH_TOKEN=YOUR_AUTH_TOKEN
```

### 5. Authentication Setup

The RESTlet uses Token-Based Authentication. You'll need to:

1. Create an Integration in NetSuite
2. Create Access Tokens
3. Set up the authentication token in your Laravel configuration

### 6. Test the Integration

1. Start your Laravel application
2. Navigate to the Books management page
3. Create, update, or delete books to test the RESTlet integration
4. Check NetSuite logs for RESTlet execution

## RESTlet Endpoints

The RESTlet accepts POST requests with the following structure:

### Upsert Book
```json
{
  "action": "upsert",
  "data": {
    "isbn": "978-1234567890",
    "title": "Sample Book",
    "author": "John Doe",
    "description": "Book description",
    "price": 29.99,
    "pages": 300,
    "publisher": "Sample Publisher",
    "published_date": "2023-01-01",
    "genre": "Fiction",
    "language": "English",
    "stock": 100,
    "is_active": true,
    "local_id": 123
  }
}
```

### Delete Book
```json
{
  "action": "delete",
  "data": {
    "netsuite_item_id": "12345",
    "isbn": "978-1234567890",
    "local_id": 123
  }
}
```

### Fetch Books
```json
{
  "action": "fetch",
  "data": {
    "genre": "Fiction",
    "author": "John",
    "is_active": true
  }
}
```

## Troubleshooting

1. **Custom Fields Not Found**: Ensure all custom fields are created and have the correct internal IDs
2. **Authentication Errors**: Verify your integration setup and access tokens
3. **Script Errors**: Check NetSuite script logs for detailed error messages
4. **Permission Issues**: Ensure the integration has proper permissions for Item records

## Notes

- The RESTlet creates Inventory Items in NetSuite
- Books are identified by ISBN in custom fields
- The script handles both creation and updates of existing items
- All operations are logged for debugging purposes
