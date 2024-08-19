# Orders Management

This repository delivers the following functionalities via a REST API and a Console Command:

1. Ability to create a new order
2. Ability to modify the status of a given order based on its order identifier
3. A console command to find all orders that are past their delivery date and then updated their order status to `delayed`
4. Ability to retrieve orders stored in the database based on the following scenarios:
   - Fetch a specific order by its order identifier
   - Fetch orders that match that a given order status
   - Returning all orders within the database if none of the aforementioned options are provided

# Additional Information
These are details that would enable evaluation of the project.

### Console Command
The console command has the following blueprint: <br>

```
symfony console --process-delayed-deliveries orders:process-deliveries
```

The command expects a date input in the format of `YYYY-MM-DD' e.g. 2024-08-20 and this triggers the action of finding all orders with delivery dates before the given date which have a status of <strong>processing</strong> and uupdating the order status of all identified records to <strong>delayed</strong>.

### Database Fixtures
Preliminary data can be seeded into the database in order to support running the console command.

### API Documentation
The documentation for the REST API can be found at http://localhost:8000/api/doc

### Tests
Unit tests were implemented to cover the following Helper Classes within the project:

* DateHelpers
* OrdersDataHelpers
* RandomStringGenerator
