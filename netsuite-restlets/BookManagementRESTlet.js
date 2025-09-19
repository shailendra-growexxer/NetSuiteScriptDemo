/**
 * NetSuite RESTlet for Book Management
 * SuiteScript 2.x
 * 
 * This RESTlet handles CRUD operations for books in NetSuite
 * and syncs with the Laravel application
 */

/**
 * @NApiVersion 2.1
 * @NScriptType Restlet
 * @NModuleScope SameAccount
 */

define(['N/record', 'N/search', 'N/log', 'N/error'], function(record, search, log, error) {
    
    /**
     * Main RESTlet entry point
     */
    function doPost(context) {
        try {
            var requestBody = JSON.parse(context.requestBody);
            var action = requestBody.action;
            var data = requestBody.data;
            
            log.audit('RESTlet Request', {
                action: action,
                data: data
            });
            
            switch (action) {
                case 'upsert':
                    return upsertBook(data);
                case 'delete':
                    return deleteBook(data);
                case 'fetch':
                    return fetchBooks(data);
                default:
                    throw error.create({
                        name: 'INVALID_ACTION',
                        message: 'Invalid action: ' + action
                    });
            }
        } catch (e) {
            log.error('RESTlet Error', e);
            return {
                success: false,
                error: e.message
            };
        }
    }
    
    /**
     * Create or update a book in NetSuite
     */
    function upsertBook(data) {
        try {
            var itemRecord;
            var itemId = data.netsuite_item_id;
            
            // Check if item already exists
            if (itemId) {
                try {
                    itemRecord = record.load({
                        type: record.Type.INVENTORY_ITEM,
                        id: itemId
                    });
                } catch (e) {
                    // Item not found, create new
                    itemRecord = record.create({
                        type: record.Type.INVENTORY_ITEM
                    });
                }
            } else {
                // Search by ISBN to find existing item
                var searchResults = search.create({
                    type: 'item',
                    filters: [
                        ['custitem_isbn', 'is', data.isbn]
                    ],
                    columns: ['internalid']
                }).run().getRange({start: 0, end: 1});
                
                if (searchResults.length > 0) {
                    itemRecord = record.load({
                        type: record.Type.INVENTORY_ITEM,
                        id: searchResults[0].id
                    });
                } else {
                    itemRecord = record.create({
                        type: record.Type.INVENTORY_ITEM
                    });
                }
            }
            
            // Set basic item fields
            itemRecord.setValue({
                fieldId: 'itemid',
                value: 'BOOK-' + data.isbn
            });
            
            itemRecord.setValue({
                fieldId: 'displayname',
                value: data.title
            });
            
            itemRecord.setValue({
                fieldId: 'description',
                value: data.description || ''
            });
            
            itemRecord.setValue({
                fieldId: 'baseprice',
                value: data.price
            });
            
            // Set custom fields (assuming they exist in your NetSuite)
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_isbn',
                    value: data.isbn
                });
            } catch (e) {
                log.debug('Custom field custitem_isbn not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_author',
                    value: data.author
                });
            } catch (e) {
                log.debug('Custom field custitem_author not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_publisher',
                    value: data.publisher || ''
                });
            } catch (e) {
                log.debug('Custom field custitem_publisher not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_genre',
                    value: data.genre || ''
                });
            } catch (e) {
                log.debug('Custom field custitem_genre not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_pages',
                    value: data.pages || 0
                });
            } catch (e) {
                log.debug('Custom field custitem_pages not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_language',
                    value: data.language || 'English'
                });
            } catch (e) {
                log.debug('Custom field custitem_language not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_published_date',
                    value: data.published_date ? new Date(data.published_date) : null
                });
            } catch (e) {
                log.debug('Custom field custitem_published_date not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'custitem_stock',
                    value: data.stock || 0
                });
            } catch (e) {
                log.debug('Custom field custitem_stock not found');
            }
            
            try {
                itemRecord.setValue({
                    fieldId: 'isinactive',
                    value: !data.is_active
                });
            } catch (e) {
                log.debug('Field isinactive not found');
            }
            
            // Save the record
            var savedId = itemRecord.save();
            
            log.audit('Book Upserted', {
                netsuite_item_id: savedId,
                isbn: data.isbn,
                title: data.title
            });
            
            return {
                success: true,
                netsuite_item_id: savedId,
                message: 'Book upserted successfully'
            };
            
        } catch (e) {
            log.error('Upsert Book Error', e);
            return {
                success: false,
                error: e.message
            };
        }
    }
    
    /**
     * Delete a book from NetSuite
     */
    function deleteBook(data) {
        try {
            if (!data.netsuite_item_id) {
                return {
                    success: false,
                    error: 'NetSuite Item ID is required for deletion'
                };
            }
            
            record.delete({
                type: record.Type.INVENTORY_ITEM,
                id: data.netsuite_item_id
            });
            
            log.audit('Book Deleted', {
                netsuite_item_id: data.netsuite_item_id,
                isbn: data.isbn
            });
            
            return {
                success: true,
                message: 'Book deleted successfully'
            };
            
        } catch (e) {
            log.error('Delete Book Error', e);
            return {
                success: false,
                error: e.message
            };
        }
    }
    
    /**
     * Fetch books from NetSuite
     */
    function fetchBooks(filters) {
        try {
            var searchFilters = [];
            
            // Add filters if provided
            if (filters.genre) {
                searchFilters.push(['custitem_genre', 'is', filters.genre]);
            }
            
            if (filters.author) {
                searchFilters.push(['custitem_author', 'contains', filters.author]);
            }
            
            if (filters.is_active !== undefined) {
                searchFilters.push(['isinactive', 'is', !filters.is_active]);
            }
            
            var bookSearch = search.create({
                type: 'item',
                filters: searchFilters,
                columns: [
                    'internalid',
                    'itemid',
                    'displayname',
                    'description',
                    'baseprice',
                    'custitem_isbn',
                    'custitem_author',
                    'custitem_publisher',
                    'custitem_genre',
                    'custitem_pages',
                    'custitem_language',
                    'custitem_published_date',
                    'custitem_stock',
                    'isinactive'
                ]
            });
            
            var results = [];
            bookSearch.run().each(function(result) {
                results.push({
                    netsuite_item_id: result.id,
                    item_id: result.getValue('itemid'),
                    title: result.getValue('displayname'),
                    description: result.getValue('description'),
                    price: result.getValue('baseprice'),
                    isbn: result.getValue('custitem_isbn'),
                    author: result.getValue('custitem_author'),
                    publisher: result.getValue('custitem_publisher'),
                    genre: result.getValue('custitem_genre'),
                    pages: result.getValue('custitem_pages'),
                    language: result.getValue('custitem_language'),
                    published_date: result.getValue('custitem_published_date'),
                    stock: result.getValue('custitem_stock'),
                    is_active: !result.getValue('isinactive')
                });
                return true;
            });
            
            log.audit('Books Fetched', {
                count: results.length,
                filters: filters
            });
            
            return {
                success: true,
                books: results,
                count: results.length
            };
            
        } catch (e) {
            log.error('Fetch Books Error', e);
            return {
                success: false,
                error: e.message
            };
        }
    }
    
    return {
        post: doPost
    };
});
