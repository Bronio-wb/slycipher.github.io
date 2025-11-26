package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.Leccion;
import com.slycipher.slycipher.service.LeccionService;
import com.slycipher.slycipher.service.CursoService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/lecciones")
public class LeccionController {

    private final LeccionService leccionService;
    private final CursoService cursoService;

    public LeccionController(LeccionService leccionService, CursoService cursoService) {
        this.leccionService = leccionService;
        this.cursoService = cursoService;
    }

    @GetMapping
    public String listarLecciones(Model model) {
        model.addAttribute("lecciones", leccionService.findAll());
        return "lecciones/lista";
    }

    @GetMapping("/crear")
    public String crearLeccion(Model model) {
        model.addAttribute("leccion", new Leccion());
        model.addAttribute("cursos", cursoService.findAll());
        return "lecciones/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute Leccion leccion) {
        leccionService.save(leccion);
        return "redirect:/lecciones";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Integer id, Model model) {
        Leccion leccion = leccionService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido: " + id));

        model.addAttribute("leccion", leccion);
        model.addAttribute("cursos", cursoService.findAll());
        return "lecciones/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Integer id) {
        leccionService.delete(id);
        return "redirect:/lecciones";
    }

    @GetMapping("/{id}")
    public String detalle(@PathVariable Integer id, Model model) {
        Leccion leccion = leccionService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido: " + id));

        model.addAttribute("leccion", leccion);
        return "lecciones/detalle";
    }
}
