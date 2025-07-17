package com.vendor.service;

import com.vendor.model.VendorInfo;
import org.springframework.stereotype.Service;
import java.util.Map;

@Service
public class ResponseService {
    public void logResponse(VendorInfo vendor, Map<String, Object> response) {
        System.out.println("Logging vendor response:");
        System.out.println("Vendor: " + vendor.getVendorName());
        System.out.println("Response: " + response);
    }
}
