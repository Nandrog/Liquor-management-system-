package com.vendor.controller;

import com.vendor.model.VendorInfo;
import com.vendor.service.*;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api")
public class VendorController {

    private final PDFProcessor pdfProcessor;
    private final ValidatorService validator;
    private final SchedulerService scheduler;
    private final ResponseService responseService;

    public VendorController(PDFProcessor pdfProcessor, ValidatorService validator, SchedulerService scheduler, ResponseService responseService) {
        this.pdfProcessor = pdfProcessor;
        this.validator = validator;
        this.scheduler = scheduler;
        this.responseService = responseService;
    }

    // POST endpoint to validate vendor via PDF
    @PostMapping("/validate")
    public ResponseEntity<Map<String, Object>> validateVendor(@RequestBody Map<String, String> request) {
        String vendorName = request.get("vendor_name");
        String pdfBase64 = request.get("pdf_base64");

        VendorInfo vendor = pdfProcessor.extractInfo(pdfBase64, vendorName);
        boolean passed = validator.validate(vendor);

        Map<String, Object> response = new HashMap<>();
        if (passed) {
            String visitDate = scheduler.scheduleVisit(vendor);
            response.put("status", "passed");
            response.put("scheduled_visit", visitDate);
        } else {
            response.put("status", "failed");
            response.put("reason", "Vendor did not meet all validation criteria.");
        }

        responseService.logResponse(vendor, response);
        return ResponseEntity.ok(response);
    }

    // Flexible GET or POST check endpoint
    @RequestMapping(value = "/vendor/{id}/check", method = {RequestMethod.GET, RequestMethod.POST})
    public ResponseEntity<String> checkVendorById(@PathVariable("id") Long vendorId) {

        if (vendorId == 1) {
            return ResponseEntity.ok("Vendor " + vendorId + " is valid.");
        } else {
            return ResponseEntity.status(404).body("Vendor " + vendorId + " not found.");
        }
    }
}
