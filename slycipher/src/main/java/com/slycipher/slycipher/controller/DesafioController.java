package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.Desafio;
import com.slycipher.slycipher.service.DesafioService;
import com.slycipher.slycipher.service.CursoService;
import com.slycipher.slycipher.service.LenguajeService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/desafios")
public class DesafioController {

    private final DesafioService desafioService;
    private final CursoService cursoService;
    private final LenguajeService lenguajeService;

    public DesafioController(DesafioService desafioService, CursoService cursoService, LenguajeService lenguajeService) {
        this.desafioService = desafioService;
        this.cursoService = cursoService;
        this.lenguajeService = lenguajeService;
    }

    @GetMapping
    public String listar(Model model) {
        model.addAttribute("desafios", desafioService.findAll());
        return "desafios/lista";
    }

    @GetMapping("/crear")
    public String crear(Model model) {
        model.addAttribute("desafio", new Desafio());
        model.addAttribute("cursos", cursoService.findAll());
        model.addAttribute("lenguajes", lenguajeService.findAll());
        return "desafios/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute Desafio desafio) {
        desafioService.save(desafio);
        return "redirect:/desafios";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Integer id, Model model) {
        Desafio desafio = desafioService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido " + id));

        model.addAttribute("desafio", desafio);
        model.addAttribute("cursos", cursoService.findAll());
        model.addAttribute("lenguajes", lenguajeService.findAll());

        return "desafios/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Integer id) {
        desafioService.delete(id);
        return "redirect:/desafios";
    }
}
