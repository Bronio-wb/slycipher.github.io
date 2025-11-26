package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.Logro;
import com.slycipher.slycipher.service.LogroService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/logros")
public class LogroController {

    private final LogroService logroService;

    public LogroController(LogroService logroService) {
        this.logroService = logroService;
    }

    @GetMapping
    public String listar(Model model) {
        model.addAttribute("logros", logroService.findAll());
        return "logros/lista";
    }

    @GetMapping("/crear")
    public String crear(Model model) {
        model.addAttribute("logro", new Logro());
        return "logros/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute Logro logro) {
        logroService.save(logro);
        return "redirect:/logros";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Long id, Model model) {
        Logro logro = logroService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido " + id));

        model.addAttribute("logro", logro);
        return "logros/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Long id) {
        logroService.delete(id);
        return "redirect:/logros";
    }
}
