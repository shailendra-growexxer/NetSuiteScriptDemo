# NetSuiteScriptDemo

A Laravel 12 demo with Product CRUD and Book Management featuring NetSuite integration via SuiteTalk SOAP and RESTlet APIs.

## Features

### Products Management
- Complete CRUD operations
- Bulk CSV import/delete
- NetSuite SuiteTalk SOAP integration (stubs)
- Bootstrap UI with light theme

### Books Management (Demo RESTlet)
- Complete CRUD operations with NetSuite RESTlet integration
- Real-time sync to NetSuite via RESTlet API
- Bulk CSV import/delete with NetSuite sync
- Fetch books from NetSuite with filtering
- Bootstrap UI with sync status indicators

## Requirements
- PHP 8.2+
- Composer
- SQLite (bundled)
- NetSuite account with RESTlet deployment

## Setup
```bash
cd "NetSuiteScriptDemo"
cp .env.example .env
php artisan key:generate

# Install dependencies
composer install

# Migrate & seed
php artisan migrate --graceful
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=BulkProductsSeeder
php artisan db:seed --class=BookSeeder
php artisan db:seed --class=BulkBooksSeeder

# Serve
php artisan serve
```

Visit: 
- Products: `http://127.0.0.1:8000/products`
- Books (Demo RESTlet): `http://127.0.0.1:8000/books`

## NetSuite Configuration

### SuiteTalk SOAP (Products)
Add to `.env`:
```
NETSUITE_ACCOUNT=
NETSUITE_CONSUMER_KEY=
NETSUITE_CONSUMER_SECRET=
NETSUITE_TOKEN_ID=
NETSUITE_TOKEN_SECRET=
NETSUITE_ROLE=
NETSUITE_REALM=
NETSUITE_APP_ID=
```

### RESTlet API (Books)
Add to `.env`:
```
NETSUITE_RESTLET_URL=https://YOUR_ACCOUNT.restlets.api.netsuite.com/app/site/hosting/restlet.nl?script=YOUR_SCRIPT_ID&deploy=YOUR_DEPLOY_ID
NETSUITE_SCRIPT_ID=YOUR_SCRIPT_ID
NETSUITE_DEPLOY_ID=YOUR_DEPLOY_ID
NETSUITE_AUTH_TOKEN=YOUR_AUTH_TOKEN
```

## RESTlet Deployment

1. **Create Custom Fields** in NetSuite Item record:
   - ISBN (custitem_isbn)
   - Author (custitem_author)
   - Publisher (custitem_publisher)
   - Genre (custitem_genre)
   - Pages (custitem_pages)
   - Language (custitem_language)
   - Published Date (custitem_published_date)
   - Stock (custitem_stock)

2. **Deploy RESTlet**:
   - Copy `netsuite-restlets/BookManagementRESTlet.js` to NetSuite
   - Create script deployment
   - Configure authentication

3. **Update Laravel config** with RESTlet URLs and credentials

See `netsuite-restlets/README.md` for detailed deployment instructions.

## CSV Import Formats

### Products CSV Headers:
`sku,barcode,name,description,price,cost,stock,uom,category,brand,is_active`

### Books CSV Headers:
`isbn,title,author,description,price,pages,publisher,published_date,genre,language,stock,is_active`

## Database
- **Products**: 15,000+ seeded records
- **Books**: 8,000+ seeded records
- SQLite database with full schema
