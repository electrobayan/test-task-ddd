## Project Architecture Overview

The task was fulfilled using the same architectural approach that was initially presented in the project. I created a separate module for invoices.
All task requirements were met.

### Presentation Layer
In the presentation layer, you can find:
- Controllers
- Request Validators
- Response Mappers (Resources)

### Infrastructure Layer
At the infrastructure level, there are:
- **Persistence Models**: Work with the database.
- **Providers**: Contain class injections with the corresponding class implementations and an event listener.
- Repository Implementation
- NotificationService Implementation

### Application Layer
- The application layer contains:
- Event Listener for Notifications
- Service Implementations:
  - Invoice creation
  - Invoice viewing
  - Invoice sending
- **Utility Classes**: A separate class for calculating product line totals and invoice totals.
- **Abstract Class**: Provides basic functionality to reuse common code.

### Domain Layer
The domain layer includes:
- Entities:
  - Invoice
  - ProductLine
- Custom Exceptions
  Interfaces:
  - Repository interface
  - NotificationService interface

### Unit Tests
Additionally, you can find some basic unit tests that cover the core business logic.
