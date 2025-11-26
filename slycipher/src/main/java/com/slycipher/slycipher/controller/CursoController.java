package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.Curso;
import com.slycipher.slycipher.service.CursoService;
import com.slycipher.slycipher.service.CategoriaService;
import com.slycipher.slycipher.service.LenguajeService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/cursos")
public class CursoController {

    private final CursoService cursoService;
    private final CategoriaService categoriaService;
    private final LenguajeService lenguajeService;

    public CursoController(CursoService cursoService, CategoriaService categoriaService, LenguajeService lenguajeService) {
        this.cursoService = cursoService;
        this.categoriaService = categoriaService;
        this.lenguajeService = lenguajeService;
    }

    // Listar cursos
    @GetMapping
    public String listarCursos(Model model) {
        model.addAttribute("cursos", cursoService.findAll());
        return "cursos/lista";
    }

    // Crear curso
    @GetMapping("/crear")
    public String crearCurso(Model model) {
        model.addAttribute("curso", new Curso());
        model.addAttribute("categorias", categoriaService.findAll());
        model.addAttribute("lenguajes", lenguajeService.findAll());
        return "cursos/crear";
    }

    // Guardar curso
    @PostMapping("/guardar")
    public String guardar(@ModelAttribute Curso curso) {
        cursoService.save(curso);
        return "redirect:/cursos";
    }

    // Editar curso
    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Integer id, Model model) {
        Curso curso = cursoService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido: " + id));

        model.addAttribute("curso", curso);
        model.addAttribute("categorias", categoriaService.findAll());
        model.addAttribute("lenguajes", lenguajeService.findAll());

        return "cursos/editar";
    }

    // Eliminar curso
    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Integer id) {
        cursoService.delete(id);
        return "redirect:/cursos";
    }

    // Mostrar detalles de curso
    @GetMapping("/{id}")
    public String detalle(@PathVariable Integer id, Model model) {
        Curso curso = cursoService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido: " + id));

        model.addAttribute("curso", curso);
        return "cursos/detalle";
    }
}
