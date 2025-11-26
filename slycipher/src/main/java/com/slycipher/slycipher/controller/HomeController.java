package com.slycipher.slycipher.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class HomeController {

    @GetMapping("/")
    public String home() {
        return "index";  // Retorna la vista "index.html"
    }

    @GetMapping("/inicio")
    public String inicio() {// Nueva ruta /inicio
        return "inicio";  
    }
}
