module com.mycompany.bornetapashouse {
    requires javafx.controls;
    requires javafx.fxml;
    requires java.sql;
    requires spring.core; 
    requires spring.beans; 
    requires spring.context;
    requires com.google.gson;

    opens com.mycompany.bornetapashouse to com.google.gson, javafx.fxml;
    opens com.mycompany.bornetapashouse.DTO to com.google.gson;
    opens com.mycompany.bornetapashouse.Controllers to javafx.fxml;
    exports com.mycompany.bornetapashouse;
    exports com.mycompany.bornetapashouse.Controllers;
    requires spring.web;
}
