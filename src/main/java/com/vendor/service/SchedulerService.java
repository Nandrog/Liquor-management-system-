package com.vendor.service;

import com.vendor.model.VendorInfo;
import org.springframework.stereotype.Service;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

@Service
public class SchedulerService {
    public String scheduleVisit(VendorInfo vendor) {
        LocalDateTime visit = LocalDateTime.now().plusDays(2);
        return visit.format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm"));
    }
}