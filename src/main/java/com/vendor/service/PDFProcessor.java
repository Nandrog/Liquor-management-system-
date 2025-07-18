package com.vendor.service;
/*
import com.vendor.model.VendorInfo;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import org.springframework.stereotype.Service;
import java.util.Base64;

@Service
public class PDFProcessor {
    public VendorInfo extractInfo(String base64Pdf, String name) {
        byte[] pdfBytes = Base64.getDecoder().decode(base64Pdf);

        VendorInfo vendor = new VendorInfo();
        vendor.setVendorName(name);

        try (PDDocument document = PDDocument.load(pdfBytes)) {
            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);

            for (String line : text.split("\n")) {
                line = line.trim();
                if (line.startsWith("Financial Score:")) {
                    int score = Integer.parseInt(line.split(":")[1].trim());
                    vendor.setFinancialScore(score);
                } else if (line.startsWith("Reputation Rating:")) {
                    double rating = Double.parseDouble(line.split(":")[1].trim());
                    vendor.setReputationRating(rating);
                } else if (line.startsWith("Compliance Documents:")) {
                    boolean compliant = Boolean.parseBoolean(line.split(":")[1].trim());
                    vendor.setComplianceDocs(compliant);
                }
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

        return vendor;
    }

}*/

import com.vendor.model.VendorInfo;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import org.springframework.stereotype.Service;

import java.io.ByteArrayInputStream;
import java.util.Base64;

@Service
public class PDFProcessor {

    public VendorInfo extractInfo(String base64Pdf, String name) {
        byte[] pdfBytes = Base64.getDecoder().decode(base64Pdf);

        VendorInfo vendor = new VendorInfo();
        vendor.setVendorName(name);

        try (PDDocument document = PDDocument.load(new ByteArrayInputStream(pdfBytes))) {
            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);

            for (String line : text.split("\n")) {
                line = line.trim();
                if (line.startsWith("Financial Score:")) {
                    int score = Integer.parseInt(line.split(":")[1].trim());
                    vendor.setFinancialScore(score);
                } else if (line.startsWith("Reputation Rating:")) {
                    double rating = Double.parseDouble(line.split(":")[1].trim());
                    vendor.setReputationRating(rating);
                } else if (line.startsWith("Compliance Documents:")) {
                    boolean compliant = Boolean.parseBoolean(line.split(":")[1].trim());
                    vendor.setComplianceDocs(compliant);
                }
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

        return vendor;
    }
}

